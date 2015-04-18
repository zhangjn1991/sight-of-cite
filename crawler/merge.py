import json, glob

all = []

for filename in glob.glob('*.json'):
	f = open(filename)
	obj = json.load(f);
	obj['msid'] = filename.replace('.json','')
	all.append(obj)
	f.close()
f = open('all.txt','w')
json.dump(all,f)
f.close()