nick=dict()
name = input("Enter file:")
if len(name) < 1 : name = "mbox-short.txt"
handle = open(name)

for line in handle:
    line=line.rstrip()
    words=line.split()


    if len(words)<1 or words[0]!="From" :
        continue
    #update counter
    nick[words[1]]=nick.get(words[1],0)+1

#checking dictionary
largest=0
word=None
for k,v in nick.items():
    if v>largest:
        largest=v
        word=k
print(word,largest)
input()
