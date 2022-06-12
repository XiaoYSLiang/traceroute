# !/root/anaconda3/bin
# @Filename :main.py
# @Time     :2022/5/30 0:34
# @Author   :Hu Yifeng

import requests
import sys
import os


class Address:
    province = ''  # 省份
    city = ''  # 城市
    adcode = ''  # adcode编码
    loca = ''  # 经纬度

    def __init__(self, p, c, ad):
        self.province = p
        self.city = c
        self.adcode = ad


pathIP = []  # 中间的路径
location = []


def GetIP(endIP):
    global pathIP
    # 从windows连接到服务器上，在windows上进行测试用
    # ssh = paramiko.SSHClient()
    # username = 'root'
    # hostname = '120.25.159.35'
    # password = 'Hyf20030312'
    # ssh.load_system_host_keys()
    # ssh.connect(hostname, 22, username, password)
    # stdin, stdout, stderr = ssh.exec_command('traceroute -q 1 -m 5 -n ' + endIP)
    # traceroute = str(stdout.read().decode('utf-8')).split('\n')

    # 调用系统的traceroute命令，并把结果返回
    traceroute = os.popen('traceroute -q 1 -m 10 -n ' + endIP)
    traceroute = traceroute.read().split('\n')

    # 将ip地址拿出来放进pathIP
    if len(traceroute) > 0:
        traceroute.pop()  # 将最后一个空行删掉
        # 把每一行用空格分开把ip地址拿出来
        for i in range(1, len(traceroute)):
            pathInfo = traceroute[i].split()
            if pathInfo[1] != '*':
                pathIP.append(pathInfo[1])
        endIP = traceroute[0].split()[3]
        endIP = endIP[1: len(endIP) - 2]
        pathIP.append(endIP)  # 放目标ip
        pathIP.insert(0, '120.25.159.35')  # 放服务器ip
    else:
        print('输入错误')
    return


def GetAddress():
    global location
    key = '9a1ee8f479071e3cce43c62d6876406e'
    # 获取省市地址
    for i in range(len(pathIP)):
        url = 'https://restapi.amap.com/v3/ip?ip={0}&output=json&key={1}'.format(pathIP[i], key)
        js = requests.get(url)  # 按照html协议获取资源
        response = js.json()  # 解析获得的json
        if len(response['city']) and len(response['province']):
            location.append(Address(response['province'], response['city'], response['adcode']))

    # 获得经纬度地址
    i = 0
    while i < len(location):
        url = 'https://restapi.amap.com/v3/geocode/geo?key={0}&address={1}&adcode={2}'\
            .format(key, location[i].province + location[i].city, location[i].adcode)
        js = requests.get(url)
        response = js.json()
        if response['status'] == '1':
            location[i].loca = response['geocodes'][0]['location']
            i = i + 1
        else:
            del location[i]

    # 删掉连续相同的地址
    i = 0
    while i < len(location) - 1:
        if location[i].loca == location[i + 1].loca:
            del location[i]
        else:
            i = i + 1
    for i in range(len(location)):
        print(location[i].loca)

    return


# def GetMap():
#     key = '9a1ee8f479071e3cce43c62d6876406e'
#     paths = ''  # 所有的经纬度地址连接起来
#     for i in range(len(location)):
#         paths = paths + ';' + location[i].loca
#     paths = paths[1: len(paths)]
#     url = 'https://restapi.amap.com/v3/staticmap?zoom=3&size=750*750&location=104.428612,32.539425&scale=2&output=json' \
#           '&size=1024*1024&paths=,,,,:{0}&markers=mid,0xFF0000,A:{1}&key={2}'\
#         .format(paths, paths, key)
#     response = requests.get(url)
#     file = 'lena.png'
#     f = open(file, 'wb')
#     f.write(response.content)
#     f.close()


def main():
    GetIP(sys.argv[1])
    GetAddress()
    return


if __name__ == '__main__':
    main()
