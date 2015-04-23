import json,csv,re,string

def try_append(row, item, attr):
	if(item.has_key(attr)):
		row.append(item[attr])
	else:
		row.append("null")
	return

all = []
with open("all_papers.json") as f:	
	all = json.load(f)
	f.close()

rows = []
rows.append(['id','msid','title','abstract','cite_count','DOI','location'])
for item in all:	
	row = []
	for attr in ['id','msid','title','abstract','cite_count','DOI']:
	 	try_append(row,item,attr)

	if(item.has_key('location') and item['location'].has_key('name')):
		row.append(item['location']['name'])
	else:
		row.append("null")

	for i in range(0,len(row)):
		row[i] = re.sub(r'[^\x00-\x7F]+','',  row[i])

	rows.append(row)

f = open('all_papers.csv','w')
writer = csv.writer(f)
writer.writerows(rows)
f.close()


with open("all_authors.json") as f:	
	all = json.load(f)
	f.close()

rows = []
rows.append(['id','msid','name','pub_count','cite_count','affiliation','photo_url','fields'])
for item in all:	
	row = []
	for attr in ['id','msid','name','pub_count','cite_count','affiliation','photo_url']:
	 	try_append(row,item,attr)

	if(item.has_key('fields')):
		row.append(string.join(item['fields'],', '))
	else:
		row.append("null")

	for i in range(0,len(row)):
		row[i] = re.sub(r'[^\x00-\x7F]+','',  row[i])

	rows.append(row)

f = open('all_authors.csv','w')
writer = csv.writer(f)
writer.writerows(rows)
f.close()


















