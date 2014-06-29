import MySQLdb

# Open database connection
db = MySQLdb.connect("localhost","web_bmi","test","questionnaire" )
cur = db.cursor()
#reading the file
skipped = open('C:\Users\Niesmo\Documents\Visual Studio 2013\Projects\Questionnaire\Questionnaire\scripts\skipped.txt', 'a+b')
file = open('C:\Users\Niesmo\Documents\Visual Studio 2013\Projects\Questionnaire\Questionnaire\scripts\data.txt', 'r')
catFile = open('C:\Users\Niesmo\Documents\Visual Studio 2013\Projects\Questionnaire\Questionnaire\scripts\categories.txt', 'r')


#functions
'''
def cat_exist(cat):
    cur.execute("SELECT id FROM category WHERE name='"+cat+"'")
    return cur.rowcount != 0 

def insert_cat(data):
    if data[2] == '0':
        data[2] = "NULL"
    print "INSERT INTO category VALUES( "+data[1]+", '"+data[0]+"', "+data[2]+")"
    cur.execute("INSERT INTO category VALUES( "+data[1]+", '"+data[0]+"', "+data[2]+")")
    db.commit()

header = catFile.readline()
header = header.strip('\n').split('\t')
print header
for line in catFile:
    data = line.strip('\n').split('\t')
    
    #catName = data[0]
    #catExist = cat_exist(catName)
    insert_cat(data)
    
'''

'''
def questionnaire_exist(q_name):
    cur.execute("SELECT * FROM questionnaire WHERE name = \""+q_name+"\"")
    return cur.rowcount != 0

def insert_questionnaire(q_name):
    if questionnaire_exist(q_name) == False:

        #print "INSERT INTO questionnaire VALUES (NULL, \""+q_name+"\",'COMPELETE')"

        cur.execute("INSERT INTO questionnaire VALUES (NULL, \""+q_name+"\",'COMPLETE')")
        db.commit()


header = file.readline()
header = header.strip('\n').split('\t')
print header
for line in file:
    data= line.strip('\n').split('\t')
#    cat_id = data[3]
    
    if len(data) < 5:
        skipped.writelines(line)
        print "SKIPPING : ", data
        continue

    questionnaire_name = data[4]
    insert_questionnaire(questionnaire_name)
'''

def get_q_id(q_name):
    cur.execute("SELECT id FROM questionnaire WHERE name = \""+q_name+"\"")
    return cur.fetchall()[0][0]

def inser_question(data, q_id, cat_id):
    if data[6] == "":
        data[6] = "NULL"
    if data[7] == "N/A":
        data[7] = "NULL"
    print "INSERT INTO question VALUES (NULL, \""+MySQLdb.escape_string(data[8])+"\", \""+data[5]+"\", \""+data[7]+"\", "+data[6]+", NULL, "+cat_id+")"
    cur.execute("INSERT INTO question VALUES (NULL, \""+MySQLdb.escape_string(data[8])+"\", \""+data[5]+"\", \""+data[7]+"\", "+data[6]+", NULL, "+cat_id+")")
    db.commit()
    qu_id = cur.lastrowid
    cur.execute("INSERT INTO questionnaire_question VALUES (NULL, "+str(q_id)+", "+str(qu_id)+" )")
    db.commit()

header = file.readline()
header = header.strip('\n').split('\t')
print header
for line in file:
    data= line.strip('\n').split('\t')

    if len(data) < 5:
        #skipped.writelines(line)
        print "SKIPPING : ", data
        continue

    cat_id = data[3]
    questionnaire_name = data[4]

    q_id = get_q_id(questionnaire_name)
    inser_question(data,q_id, cat_id)
    



skipped.close()