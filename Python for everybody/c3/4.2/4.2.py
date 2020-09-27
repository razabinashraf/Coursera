# To run this, download the BeautifulSoup zip file
# http://www.py4e.com/code3/bs4.zip
# and unzip it in the same directory as this file

import urllib.request, urllib.parse, urllib.error
from bs4 import BeautifulSoup
import ssl

# Ignore SSL certificate errors
ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

url = input('Enter URL: ')
c = input('Enter Count: ')
count = int(c)
position = input('Enter Position: ')

while (count>0):
    html = urllib.request.urlopen(url, context=ctx).read()
    soup = BeautifulSoup(html, 'html.parser')
    # Retrieve all of the anchor tags
    tags = soup('a')
    pos = 0
    #print('\n\n!!!!!!Retr from',url,'\n\n')
    for tag in tags:
        #print(tag.get('href'))
        #print(tag.contents[0])
        pos = pos+1
        if (pos == int(position)):
            url=tag.get('href')
            #print('\n\n!!!!!!CHANGED','\n\n')
            break
    #print(count)
    count = count-1

print(tag.contents[0])
