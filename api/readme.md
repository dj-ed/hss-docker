**RUN SWAGGER**
<br><br>
`composer update `<br>
`npm install or update` <br>
`npm run dev` <br>

add to .env
<br><br>
`L5_SWAGGER_GENERATE_ALWAYS=true` <br>
`true` / `false` - auto generated changes in API Controllers
<br><br>
**SWAGGER DOCS API** 
<br><br>
url: `domain.name/api/documentation`
<br>
<br>
**SPHINX SEARCH** 
<br>
`composer update `<br>
<br>
*install MAC*
<br>
`brew install sphinx --mysql` <br>
copy `sphinx.config` to `/usr/local/Cellar/sphinx/2.2.11/etc/` and RUN
<br><br>
_**commands from terminal MAC**_
<br>
`searchd` - run<br>
`searchd --stop` - stop<br>
`indexer --rotate --all` - index data from config<br>
<br><br>
*install Linux* 
<br><br>
1. sudo apt-get install sphinxsearch
2. Configuring Sphinx:
copy sphinx.conf from project to  /etc/sphinxsearch/sphinx.conf
3. sudo indexer --rotate --all
4. sudo service sphinxsearch start
<br><br>




