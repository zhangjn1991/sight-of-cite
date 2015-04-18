import json
all = []
with open('all.txt') as f:
	all = json.load(f);
	f.close()

all_authors = []

for paper in all:
	if('authors' not in paper):
		continue
	for author in paper['authors']:
		all_authors.append(author)

with open('all_authors.json','w') as f:
	json.dump(all_authors,f)
	f.close()
