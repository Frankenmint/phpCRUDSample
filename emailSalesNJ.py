#!/usr/bin/python
# encoding: UTF-8

import smtplib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEBase import MIMEBase
from email.MIMEText import MIMEText
from email import Encoders
import os
import MySQLdb
import string
from datetime import datetime, timedelta
import csv
import requests
from time import sleep
import json
import subprocess


yesterday = datetime.strftime(datetime.now() - timedelta(1), '%m-%d-%Y')


#email = [ '', '', '']
email = ['frankenmint@gmail.com']
subject 	= "Daily Sales Totals Rewrite"
host 		= "smtp.gmail.com" 
usrnme 		= "" 
pswd 		= ""
from_addr   = ""



db = MySQLdb.connect(host="",    # your host, usually localhost
                     user="",         # your username
                     passwd= "",  # your password
                     db=  "")        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()


#generate the fees to be calculated
cur.execute("SELECT DISTINCT coinTxId FROM websitePayouts")
txIds = cur.fetchall()

fees = 0
for elements in txIds:

	print elements[0]
        output = subprocess.check_output(["litecoin-cli", 'gettransaction', elements[0]])
        output = json.loads(output)
        #output = output.strip("\n")
        eachOne = output['fee'] * -1.0
        print eachOne
        fees = fees + eachOne

	'''
	#test = '6e5b0cf443210bfab5c19253abc87a7dae4225ebb728a37c90d401478348020b'
	#r = requests.get('https://api.blockcypher.com/v1/ltc/main/txs/' + elements[0]+'')
	r = requests.get('https://ltc.blockr.io/api/v1/tx/info/' + elements[0]+'')

	result = r.json()
	#eachOne =  result['fees']
	eachOne =  result['data']['fee'] #ltc.blockr.io
	
	eachOne = float(eachOne)
	#eachOne = float(eachOne)/100000000.0 #blockcypher
	fees = fees + eachOne
	print eachOne
	sleep(1)
	'''


print fees


cur.execute("SELECT walletBal FROM configs")
data = cur.fetchone()

walletBalance = data[0]


# Use all the SQL you like
cur.execute("SELECT createdOn, email, amtInFiat, amtInCoin, orderNumber, employeeId FROM transactions where date(createdOn) = curdate() - interval 1 day")
data = cur.fetchall()

txCount = len(data);
print "Count of Transactions: " + str(txCount)

gross = 0
amtInCoin = 0.0

for i in data:
	gross = gross + i[2]
	amtInCoin = amtInCoin + float(i[3])


print "Total Credits Equivalent: $" + str(gross) 
print "Total Fees:  L" + str(fees)
print "Total Coin Paid Out: L" + str(amtInCoin) 
print "Total Coin Including Fees: L"+ str(amtInCoin + fees)
print "Current LTC balance is: L" + str(walletBalance)


db.close()


fp = open('/home/ltcnjusr/sales_'+yesterday+'.csv', 'w')    # You pick a name, it's temporary
attach_file = csv.writer(fp)
attach_file.writerow(['Date And Time', 'Backpage Email', 'Number of Credits', 'LTC Amount', 'Reference #', 'Agent'])
attach_file.writerows(data)
fp.close()

mailServer = smtplib.SMTP(host, 587)
mailServer.ehlo()
mailServer.starttls()
mailServer.ehlo()

for i in email:
	msg = MIMEMultipart()
	msg['From'] = usrnme
	msg['To'] = i
	msg['Subject'] =  subject
	part = MIMEBase('application', "octet-stream")
	textPart = MIMEText('\n' + "Count of Transactions: " + str(txCount) + '\n' + "Total Credits Paid Out: $" + str(round(gross, 2)) + '\n' + "LTC Paid Out: " + str(amtInCoin) + "\n LTC Fees Paid: "+str(fees)+ "\n LTC Subtotal: "+ str((amtInCoin + fees)) +"\n\nCurrent LTC balance is: " + str(walletBalance) + "\n\n", 'plain')
	msg.attach(textPart)
	part.set_payload(open('/home/ltcnjusr/sales_'+yesterday+'.csv', "rb").read())
	Encoders.encode_base64(part)
	part.add_header('Content-Disposition', "attachment; filename=sales_"+yesterday+".csv")
	msg.attach(part)
	text = msg.as_string()
	mailServer.login(usrnme, pswd)
	mailServer.sendmail(usrnme, i, text)

# Should be mailServer.quit(), but that crashes...
mailServer.close()


