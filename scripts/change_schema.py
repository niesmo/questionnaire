import MySQLdb

# Open database connection
db = MySQLdb.connect("localhost","web_bmi","test","questionnaire" )
cur = db.cursor()

#getting all the questionnaires
cur.execute("SELECT * FROM questionnaire");
questionnaires = cur.fetchall();
for questionnaire in questionnaires:
    qn_id = questionnaire[0]

    #getting the first question in the questionnaire
    cur.execute("SELECT author, year FROM question as q, questionnaire_question as qq WHERE qq.question_id = q.question_id AND qq.questionnaire_id = "+str(qn_id)+" LIMIT 1")
    question = cur.fetchone()
 
    author = question[0]
    year = question[1]

    if year is None:
        year = "NULL"


    #updating the questionnaire
    print "UPDATE questionnaire SET author = '"+str(author)+"' AND year = "+str(year)+" WHERE questionnaire_id = "+str(qn_id)
    cur.execute("UPDATE questionnaire SET author = '"+str(author)+"', year = "+str(year)+" WHERE questionnaire_id = "+str(qn_id))
    db.commit()
    

    #print questionnaire
    #print question
    #break