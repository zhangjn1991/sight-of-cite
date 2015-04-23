import json, csv, io
def writeJSON(path,obj):
	f = io.open(path,'w',encoding='utf8')
	f.write(json.dumps(obj,f,ensure_ascii=False))
	f.close()

def getMSId(item):
	return int(item['msid'])

all_papers = []
all_authors = []

with open("all_authors.json") as f:
	all_authors = json.load(f)
	f.close()

with open("all_papers.json") as f:
	all_papers = json.load(f)
	f.close()

author_id_dict = {}
paper_author_list = []


for author in all_authors:	
	author_id_dict[author[u'msid']]=author['id']	

for paper in all_papers:
	if(not paper.has_key('authors')):
		continue
	for author in paper[u'authors']:
		paper_author_list.append([paper[u'id'],author_id_dict[author[u'msid']]])

with open("paper_author.csv",'w') as f:
	writer = csv.writer(f)
	writer.writerows(paper_author_list)
	f.close()








