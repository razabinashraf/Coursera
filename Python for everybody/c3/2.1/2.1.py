import re
sum=0
handle=open("findsum.txt")
for line in handle:
    line=line.rstrip()
    numbers=re.findall('[0-9]+',line)
    if len(numbers)==0 :
        continue
    for i in range(len(numbers)):
        sum=sum+int(numbers[i])
print (sum)
