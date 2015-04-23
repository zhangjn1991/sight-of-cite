# PaperInfo .paper-info
# Title: .title-span.text
# Citation Count: .citation a .text remove 'Citations: '
# Author .author-name-tooltip
#      Author ID Author[href] remove not number
#      Author Name Author.text
# Abstract .abstract span.text
# DOI .divDOI a.text
# Location .conference-name
#      Location Link: Location[href]
#      Location Name Location.text

from bs4 import BeautifulSoup
import re, json, urllib2, csv, time

def getNumber(s):
	 return re.search('[0-9]+',s).group(0)


def getPaper(url):
	soup = BeautifulSoup(open(url))

	paper = {}
	paper_info = soup.find(class_='paper-info')
	if(paper_info is None):
		return paper

	title = paper_info.find(class_='title-span')
	if(title is not None):
		paper['title'] = title.text
	
	cite_count = paper_info.find(class_='citation')
	if(cite_count is not None):
		cite_count = cite_count.find('a')
		if(cite_count is not None):
			paper['cite_count'] = getNumber(cite_count.text)
	

	authors = [];

	for author in paper_info.findAll(class_='author-name-tooltip'):
		obj={}
		obj['name']=author.text
		if(author.has_attr('href')):
			obj['msid']=getNumber(author['href'])
		authors.append(obj)

	paper['authors']=authors
	
	abstract = paper_info.find(class_='abstract')
	if(abstract is not None):
		abstract = abstract.find('span')
		if(abstract is not None):
			paper['abstract'] = abstract.text


	doi = paper_info.find(class_='divDOI')
	if(doi is not None):
		doi = doi.find('a')
		if(doi is not None):
			paper['DOI'] = doi.text
	

	paper['location'] = {}
	conference_name = paper_info.find(class_='conference-name')
	if(conference_name is not None):
		paper['location']['name'] = conference_name.text
		if(conference_name.has_attr('href')):
			paper['location']['url'] = conference_name['href']
	

	return paper

def scrapePaper(paper_msid):	
	url = 'http://academic.research.microsoft.com/Publication/' + paper_msid
	f = open('papers/'+paper_msid+'.json','w')
	obj = getPaper(url)
	obj['msid']=paper_msid
	json.dump(obj,f)
	f.close()

f = open('all_paper_ids.txt')
reader = csv.reader(f)
all_paper_ids = []
for row in reader:
	all_paper_ids.append(row[0])
f.close()

# for i in range(0,5):
# # for i in range(0,len(all_paper_ids)):
# 	cur_id = all_paper_ids[i]
# 	print 'Cur:'+ cur_id + ' ' + str(i+1) + '/' + str(len(all_paper_ids))
# 	try:
# 		scrapePaper(cur_id)
# 		time.sleep(2)
# 	except:
# 		print 'skip: ' + str(cur_id)

# print 'Done'



