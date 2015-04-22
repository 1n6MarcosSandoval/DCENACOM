#-*- encoding: utf-8 -*-
import cx_Oracle
conOracle = cx_Oracle.connect('cenacom/jxkGR@10.2.233.164/orcl')
cursor = conOracle.cursor()
ide=[]

def update():
		query="truncate table cenacom.sess"
		print query
		cursor.execute(query)
		#cursor.execute("commit")
update()
conOracle.close()