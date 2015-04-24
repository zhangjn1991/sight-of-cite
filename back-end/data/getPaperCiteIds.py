import json,csv

all = []
with open('all_papers.json') as f:
	all = json.load(f)
	f.close()
dict = {};

for paper in all:
	dict[paper['msid']] = paper['id']

cite_ids = []
with open('cite-ids.txt') as f:
	reader = csv.reader(f)
	for row in reader:
		cite_ids.append([dict[row[0]],dict[row[1]]])
	f.close()

with open('cite-ids.csv','w') as f:
	writer = csv.writer(f)
	writer.writerows(cite_ids)
	f.close()