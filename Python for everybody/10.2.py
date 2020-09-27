nick=dict()
lst=list()
name = input("Enter file:")
if len(name) < 300 : name = "mbox-short.txt"
handle = open(name)


for line in handle:
    line=line.rstrip()
    words=line.split()
    if len(words)<1 or words[0]!="From":
        continue
    hrs=words[5].split(":")

    #update counter
    nick[hrs[0]]=nick.get(hrs[0],0)+1


for key,value in nick.items():
    lst.append((key,value))
lst=sorted(lst)


for k,v in lst:
    print(k,v)
