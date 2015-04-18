# Author Page
#      author_card '.author-card'
#           name: #ctl00_MainContent_AuthorItem_authorName
#           photo_url: #ctl00_MainContent_AuthorItem_imgAuthorPhoto [src]
#           affiliation: #ctl00_MainContent_AuthorItem_affiliation
#           pub_count: #ctl00_MainContent_AuthorItem_publication
#           cite_count: #ctl00_MainContent_AuthorItem_citedBy
#           fields (array):
#           field_list:     author_card(text=re.compile(r'Fields:'))[0].parent
#                field_name: field_list -> 'a' .text

from bs4 import BeautifulSoup
import re, json, urllib2, csv, time, io

def getNumber(s):
	 return re.search('[0-9]+',s).group(0)

def getAuthor(url):
	soup = BeautifulSoup(urllib2.urlopen(url))
	# soup = BeautifulSoup(open(url))

	author = {}
	author_card = soup.find(class_='author-card')
	if(author_card is None):
		return author

	tmp = author_card.find(id="ctl00_MainContent_AuthorItem_authorName")
	if(tmp is not None):
		author['name']= tmp.text

	tmp = author_card.find(id="ctl00_MainContent_AuthorItem_imgAuthorPhoto")
	if(tmp is not None and tmp.has_attr('src')):
		author['photo_url']= tmp['src']

	tmp = author_card.find(id="ctl00_MainContent_AuthorItem_affiliation")
	if(tmp is not None):
		author['affiliation']= tmp.text

	tmp = author_card.find(id="ctl00_MainContent_AuthorItem_publication")
	if(tmp is not None):
		author['pub_count']= tmp.text

	tmp = author_card.find(id="ctl00_MainContent_AuthorItem_citedBy")
	if(tmp is not None):
		author['cite_count']= tmp.text

	fields = []
	tmp = author_card(text=re.compile(r'Fields:'))
	if(len(tmp)>0):
		tmp = tmp[0].parent
		for field in tmp('a'):
			if(not field.text.isspace()):
				fields.append(field.text)
		author['fields'] = fields

	return author

def writeJSON(path,obj):
	f = io.open(path,'w',encoding='utf8')
	f.write(json.dumps(obj,f,ensure_ascii=False))
	f.close()

def scrape(msid):	
	url = 'http://academic.research.microsoft.com/Author/' + msid
	writeJSON('author_results/'+str(msid)+'.json',getAuthor(url))




f = open('all_author_ids.txt')
reader = csv.reader(f)
all_author_ids = []
for row in reader:
	all_author_ids.append(row[0])
f.close()

# for i in range(0,len(all_author_ids)):
for i in range(0,3):
	cur_id = all_author_ids[i]
	print 'Cur:'+ cur_id + ' ' + str(i+1) + '/' + str(len(all_author_ids))
	try:
		scrape(cur_id)
		time.sleep(120)
	except:
		print 'skip: ' + str(cur_id)

print 'Done'