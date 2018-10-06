#include <cstdio>
#include <cstring>
#include <pthread.h>
#include <cstdlib>
#include <algorithm>
#include <windows.h>
#include <Winsock2.h>
#include <unistd.h>
#include <dir.h>
#include <direct.h>
#include <iostream>
#include <queue>
#include <dirent.h>
#include <curl/curl.h>
#include <json/json.h>
using namespace std;
const int MAXC = 1024;
struct Submission{
    char json_code[MAXC*100];
}tsub, ssub;
queue<Submission> que;
char compile_info[MAXC*10+10];



#define OJ_CI 0
#define OJ_CE 1
#define OJ_PASS 2
#define OJ_UNPASS 3
#define OJ_PENDING 4
#define OJ_RI 19

#define OJ_JUDGE_AC 10
#define OJ_JUDGE_WA 11
#define OJ_JUDGE_TLE 12
#define OJ_JUDGE_MLE 13
#define OJ_JUDGE_RE 14
#define OJ_JUDGE_PE 15

#define LANG_C 0
#define LANG_CPLUS 1

char Port[MAXC+10];
char POSTURL[MAXC+10];
char DATA_ROOT[MAXC+10];

void settings_check(const char* sets, const char* reader, const char* val, char *cps){
    if(strcmp(sets, reader) == 0)
        strcpy(cps, val);
}

void _init_settings(){
    FILE *fp = fopen("settings", "r");
    if(!fp) return ;
    char s1[MAXC], s2[MAXC];
    while(fscanf(fp, "%s%s", s1, s2) != EOF){
        settings_check(s1, "PORT", s2, Port);
        settings_check(s1, "POSTURL", s2, POSTURL);
        settings_check(s1, "DATAROOT", s2, DATA_ROOT);
    }
    fclose(fp);
}

void write_log(const char *s, int mode=0){
    FILE *fp = fopen("log.txt", "a+");
    SYSTEMTIME sys;
    GetLocalTime( &sys );
    fprintf(fp, "[%4d/%02d/%02d %02d:%02d:%02d.%03d]%s\n", sys.wYear,sys.wMonth,sys.wDay,sys.wHour,sys.wMinute, sys.wSecond,sys.wMilliseconds, s);
    fclose(fp);
    fclose(stdout);
    freopen("CON", "w", stdout);
    if(mode) printf("----ERROR----");
    printf("[%4d/%02d/%02d %02d:%02d:%02d.%03d] %s\n", sys.wYear,sys.wMonth,sys.wDay,sys.wHour,sys.wMinute, sys.wSecond,sys.wMilliseconds, s);
    fclose(stdout);
}
void *Listener(void *arg){
    WORD wVersionRequested;
    WSADATA wsaData;
    int err;
    wVersionRequested = MAKEWORD( 1, 1 );
    err = WSAStartup( wVersionRequested, &wsaData );
    if ( err != 0 ) return NULL;
    if ( LOBYTE( wsaData.wVersion ) != 1 ||
        HIBYTE( wsaData.wVersion ) != 1 ) {
        WSACleanup();
        return NULL;
    }
    SOCKET sockSrv=socket(AF_INET,SOCK_STREAM,0);

    SOCKADDR_IN addrSrv;
    addrSrv.sin_addr.S_un.S_addr=htonl(INADDR_ANY);
    addrSrv.sin_family=AF_INET;
    addrSrv.sin_port=htons(atoi(Port));

    bind(sockSrv,(SOCKADDR*)&addrSrv,sizeof(SOCKADDR));

    listen(sockSrv,5);

    SOCKADDR_IN addrClient;
    int len=sizeof(SOCKADDR);
    char tmp[MAXC*100+10], t2[100];
    write_log("Begin to listen on port");
    while(1)  {
        SOCKET sockConn=accept(sockSrv,(SOCKADDR*)&addrClient,&len);
        write_log("Received a submission on port");

        recv(sockConn,tmp,MAXC*100,0);
        strcpy(tsub.json_code, tmp);

        sprintf(t2, "OK");
        send(sockConn, t2, strlen(t2), 0);

        que.push(tsub);

        closesocket(sockConn);
    }
}
void Read_Results(char *work_dir, int data_count, Json::Value &val, int &stat, bool Running){
    stat = OJ_PASS;
    char result_path[MAXC], tmp[MAXC+10];
    for(int i=1;i<=data_count;i++){
        sprintf(result_path, "%s\\result%d.txt", work_dir, i);
        FILE *fp = fopen(result_path, "r");
        if(!fp) continue;
        Json::Value troot;
        int t1, t2, t3;
        fscanf(fp, "%d%d%d", &t1, &t2, &t3);
        if(t1 != 10)
            stat = OJ_UNPASS;
        troot["status"] = t1;
        troot["usedtime"] = t2;
        troot["usedmem"] = t3;
        troot["runinfo"] = string("You are right.");
        if(Running){
            sprintf(result_path, "%s\\user%d.out", work_dir, i);
            FILE* user_op=fopen(result_path, "r");
            if(user_op){
                int cnt = 0;
                char ch;
                while(cnt<MAXC&&(ch=fgetc(user_op))!=EOF)
                    tmp[cnt++] = ch;
                tmp[cnt] = '\0';
                troot["runinfo"]=string(tmp);
                fclose(user_op);
            }
        }
        else if(t1 == OJ_JUDGE_WA){
            sprintf(result_path, "%s\\diff%d.out", work_dir, i);
            FILE *user_diff=fopen(result_path, "r");
            if(user_diff){
                int cnt = 0;
                char ch;
                while(cnt<MAXC&&(ch=fgetc(user_diff))!=EOF)
                    tmp[cnt++] = ch;
                tmp[cnt] = '\0';
                troot["runinfo"]=string(tmp);
                fclose(user_diff);
            }
        }
        else if(t1 == OJ_JUDGE_RE){
            sprintf(result_path, "%s\\reinfo%d.out", work_dir, i);
            FILE *user_re=fopen(result_path, "r");
            long reason;
            fscanf(user_re, "%ld", &reason);
            fclose(user_re);
            switch(reason){
            case EXCEPTION_ACCESS_VIOLATION:
                troot["runinfo"]=string("EXCEPTION_ACCESS_VIOLATION");
                break;
            case EXCEPTION_ARRAY_BOUNDS_EXCEEDED:
                troot["runinfo"]=string("EXCEPTION_ARRAY_BOUNDS_EXCEEDED");
                break;
            case EXCEPTION_FLT_DENORMAL_OPERAND:
                troot["runinfo"]=string("EXCEPTION_FLT_DENORMAL_OPERAND");
                break;
            case EXCEPTION_FLT_DIVIDE_BY_ZERO:
                troot["runinfo"]=string("EXCEPTION_FLT_DIVIDE_BY_ZERO");
                break;
            case EXCEPTION_FLT_INEXACT_RESULT:
                troot["runinfo"]=string("EXCEPTION_FLT_INEXACT_RESULT");
                break;
            case EXCEPTION_IN_PAGE_ERROR:
                troot["runinfo"]=string("EXCEPTION_IN_PAGE_ERROR");
                break;
            case EXCEPTION_INT_DIVIDE_BY_ZERO:
                troot["runinfo"]=string("EXCEPTION_INT_DIVIDE_BY_ZERO");
                break;
            case EXCEPTION_INT_OVERFLOW:
                troot["runinfo"]=string("EXCEPTION_INT_OVERFLOW");
                break;
            case EXCEPTION_STACK_OVERFLOW:
                troot["runinfo"]=string("EXCEPTION_STACK_OVERFLOW");
                break;
            case EXCEPTION_PRIV_INSTRUCTION:
                troot["runinfo"]=string("EXCEPTION_PRIV_INSTRUCTION");
                break;
            case EXCEPTION_INVALID_DISPOSITION:
                troot["runinfo"]=string("EXCEPTION_INVALID_DISPOSITION");
                break;
            case EXCEPTION_FLT_OVERFLOW:
                troot["runinfo"]=string("EXCEPTION_FLT_OVERFLOW");
                break;
            }
        }
        val.append(troot);
        fclose(fp);
    }
}
/****************提交信息*****************/
size_t write_data(void *buffer, size_t size, size_t nmemb, void *userp) {
    FILE *fptr = (FILE*)userp;
    fwrite(buffer, size, nmemb, fptr);
}
void _Submit(int id, const char* sid, Json::Value runs, const char *cpinfo, bool has=true){
    write_log("Submitting");
    Json::Value root;
    root["status"] = id;
    root["sid"] = string(sid);
    root["runinfo"] = string(cpinfo);
    if(has){
        root["data"] = runs;
        //write_log(runs.toStyledString().c_str());
    }
    string ctent = "content=" + root.toStyledString();
    write_log(ctent.c_str());
    FILE *fptr;
    if ((fptr = fopen("curl.log", "w")) == NULL);
    CURL* curl;
    curl = curl_easy_init();
    curl_easy_setopt(curl, CURLOPT_URL, POSTURL);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_data);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, fptr);
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, ctent.c_str());
    curl_easy_setopt(curl, CURLOPT_POST, 1);
    int res;
    if((res=curl_easy_perform(curl)) == CURLE_OK)
        write_log("cURL send message successfully.");
    else{
        string ttt = "";
        while(res){
            ttt += (char)(res%10+'0');
            res /= 10;
        }
        string tttt(ttt.rbegin(), ttt.rend());
        write_log((string("cURL send message failed and code is ")+tttt).c_str(), 1);
    }
    curl_easy_cleanup(curl);
}
/**********判断是否存在编译错误************/
Submission check_compile_error(char *info_dir){
    char tmp[MAXC+10];
    FILE* fp = fopen(info_dir, "r");
    Submission ret;
    memset(ret.json_code, 0, sizeof ret.json_code);
    if(!fp) return ret;
    int sum_len = 0;
    while(fgets(tmp, MAXC, fp)){
        char *beg = tmp;
        while(*beg == ' ')
            beg++;
        int Len = strlen(beg);
        sum_len += Len;
        if(sum_len > 200)
            break;
        if(*beg == '\n')
            sum_len--;
        sprintf(ret.json_code, "%s%s", ret.json_code, tmp);
    }
    fclose(fp);
    if(!sum_len)
        ret.json_code[0] = '\0';
    return ret;
}
bool used[5];
int tpcount, counter_judge;
bool isInFile(const char fname[]){
    int l = strlen(fname);
    if (l <= 3 || strcmp(fname + l - 3, ".in") != 0)
        return false;
    return true;
}
void* Create_Judge(int v){
    /*********转换Json*********/
    char tmp[MAXC+10];
    Submission sub = ssub;
    int uid = v;
    Json::Value root;
    Json::Reader reader;
    write_log("Begin to test");
    if(!reader.parse(sub.json_code, root)){
        write_log("Can't parse json to array.", 1);
        used[uid] = false;
        counter_judge--;
        return NULL;
    }
    write_log("Parse Json Finished");
    char command[MAXC+10], work_dir[MAXC+10];
    /*******设置工作路径**********/
    getcwd(work_dir,  MAXC);
    sprintf(work_dir, "%s\\run%d", work_dir, uid);
    sprintf(command, "mkdir %s 2> NUL > NUL", work_dir);
    system(command);
    write_log(command);
    write_log("Reset Directory Finished");
    /********放入数据文件**********
    在这里如果从Json中找到Data，那么我们使用运行模式，评测出的结果不管，在Data的runinfo中返回用户输出
    然后如果是评测模式，我们直接放入data文件，然后在runner中判断是否有spj.exe
    这里spj.exe的模式认为1为正确0为错误2为格式错误
    *******************************/
    int data_count = 0;
    bool isRunning_mode = 0;
    if(root["data"].isNull()){
        write_log("Copy data from localhost");
        sprintf(command, "copy %s\\%s %s > NUL 2> NUL", DATA_ROOT, root["pid"].asString().c_str(), work_dir);
        system(command);
        DIR *dp = opendir(work_dir);
        dirent *dirp;
        while((dirp=readdir(dp))!=NULL){
            if(isInFile(dirp->d_name))data_count++;
        }
    }else{
        isRunning_mode = true;
        write_log("Copy data from website and return output");
        data_count = root["data"].size();
        for(int i=0;i<data_count;i++){
            sprintf(tmp, "%s\\data%d.in", work_dir, i+1);
            freopen(tmp, "w", stdout);
            cout<<root["data"][i]["input"].asString();
            fclose(stdout);
            sprintf(tmp, "%s\\data%d.out", work_dir, i+1);
            freopen(tmp, "w", stdout);
            cout<<root["data"][i]["output"].asString();
            fclose(stdout);
        }
    }
    write_log("Put Data Finished");
    /*************放入相关设置*****************/
    sprintf(tmp, "%s\\option.txt", work_dir);
    freopen(tmp, "w", stdout);
    printf("%s %s %d\n", root["mem_lmt"].asString().c_str(), root["time_lmt"].asString().c_str(), data_count);
    fclose(stdout);
    /************放入main.cpp*****************/
    if(atoi(root["language"].asString().c_str()) == LANG_CPLUS)
        sprintf(tmp, "%s\\main.cpp", work_dir);
    else
        sprintf(tmp, "%s\\main.c", work_dir);
    freopen(tmp, "w", stdout);
    cout<<root["code"].asString();
    fclose(stdout);
    sprintf(tmp, "%s\\cpinfo.txt", work_dir);
    SECURITY_ATTRIBUTES sa2={sizeof(sa2),NULL,TRUE};
    /***********限制20s编译******************/
    Json::Value sendroot;
    _Submit(OJ_CI, root["sid"].asString().c_str(), sendroot, "\0", false);
    HANDLE hOut=CreateFile(
        tmp,
        GENERIC_WRITE,
        0,
        &sa2,
        CREATE_ALWAYS,
        FILE_ATTRIBUTE_NORMAL,
        NULL
    );
    STARTUPINFO si = {sizeof(si)} ;
    PROCESS_INFORMATION pi ;
    si.dwFlags = STARTF_USESHOWWINDOW|STARTF_USESTDHANDLES;
    si.wShowWindow = SW_HIDE;
    si.hStdError = hOut;
    if(atoi(root["language"].asString().c_str()) == LANG_CPLUS)
        sprintf(command, "g++ %s\\main.cpp -o .\\run%d\\main -w -Wall -DONLINE_JUDGE", work_dir, uid);
    else
        sprintf(command, "gcc %s\\main.c -o .\\run%d\\main -w -Wall -DONLINE_JUDGE", work_dir, uid);
    write_log(command);
    while(!CreateProcess(NULL, command,NULL,NULL,TRUE,0,NULL,NULL,&si,&pi));
    WaitForSingleObject(pi.hProcess, 20000);
    long unsigned int excode;
    GetExitCodeProcess(pi.hProcess, &excode);
    CloseHandle(pi.hProcess);
    CloseHandle(pi.hThread);
    CloseHandle(hOut);
    if(excode == STILL_ACTIVE){
        write_log("Compiler runs over than 20s.", 1);
        TerminateProcess(pi.hProcess,0);
        TerminateProcess(pi.hThread,0);
        _Submit(OJ_CE, root["sid"].asString().c_str(), sendroot, "Compile TLE\0", false);
        return NULL;
    }
    /***************检查编译错误******************/
    //system(command);
    sprintf(tmp, "%s\\cpinfo.txt", work_dir);
    Submission compinfo=check_compile_error(tmp);
    if(strlen(compinfo.json_code) != 0){
        _Submit(OJ_CE, root["sid"].asString().c_str(), sendroot, compinfo.json_code, false);
        write_log("Compile error!", 1);
        sprintf(command, "rmdir /S /Q %s", work_dir);
        system(command);
        used[uid] = false;
        counter_judge--;
        return NULL;
    }else if(!isRunning_mode) _Submit(OJ_RI, root["sid"].asString().c_str(), sendroot, "\0", false);
    /************准备Running*****************/
    sprintf(command, "copy .\\runner.exe %s\\runner.exe > NUL", work_dir);
    system(command);
    sprintf(command, "%s\\runner.exe", work_dir);
    write_log("All files are ready.");
    system(command);
    int resu_end;
    /***********输出结果利用curl返回**************/
    Read_Results(work_dir, data_count, sendroot, resu_end, isRunning_mode);
    _Submit(resu_end, root["sid"].asString().c_str(), sendroot, "\0");
    sprintf(command, "rmdir /S /Q %s", work_dir);
    system(command);
    write_log("All files are deleted.");
    used[uid] = false;
    counter_judge--;
    return NULL;
}
int main(){
    _init_settings();
    if(!strlen(Port) || !strlen(POSTURL) || !strlen(DATA_ROOT))
        write_log("Read settings error", 1);
    pthread_t tid;
    pthread_create(&tid, NULL, Listener, NULL);
    while(true){
        if(que.empty()){
            Sleep(1000);
            continue;
        }
        ssub = que.front();
        write_log(ssub.json_code);
        que.pop();
        Create_Judge(0);
    }

    return 0;
}
