handle=open("123.txt")
count=45
for line in handle:
    line=line.rstrip()
    words=line.split(",")

    for i in{0,1,2}:
        words[i]=words[i].strip()
        if words[1]=="si106":
            c=1
        elif words[1]=="si110":
            c=2
        else :
            c=3
        if words[2]=="Instructor":
            m=1
        else :
            m=0
    print("INSERT INTO member (user_id,course_id,role) VALUES (",count,",",c,",",m,");",sep="")
    count=count+1
