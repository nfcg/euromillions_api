# EuroMillions API

### A Euro Millions Lottery Results API

#### Web Server Rewrite Rules.

Apache.
```
RewriteRule ^api-euromillions-xml$ /euromillions.php?format=xml
RewriteRule ^api-euromillions-json$ /euromillions.php?format=json
RewriteRule ^api-euromillions$ /euromillions.php?format=txt
```

Nginx.
```
rewrite ^/api-euromillions-xml$ /euromillions.php?format=xml;
rewrite ^/api-euromillions-json$ /euromillions.php?format=json;
rewrite ^/api-euromillions$ /euromillions.php?format=txt;
```

-----


#### Examples:


(Last)

https://nunofcguerreiro.com/api-euromillions

https://nunofcguerreiro.com/api-euromillions-xml

https://nunofcguerreiro.com/api-euromillions-json
    


(All)

https://nunofcguerreiro.com/api-euromillions?result=all

https://nunofcguerreiro.com/api-euromillions-xml?result=all

https://nunofcguerreiro.com/api-euromillions-json?result=all
    


(Date)

https://nunofcguerreiro.com/api-euromillions?result=2018-03-27

https://nunofcguerreiro.com/api-euromillions-xml?result=2018-03-27

https://nunofcguerreiro.com/api-euromillions-json?result=2018-03-27
    


(All Year)

https://nunofcguerreiro.com/api-euromillions?result=2018

https://nunofcguerreiro.com/api-euromillions-xml?result=2018

https://nunofcguerreiro.com/api-euromillions-json?result=2018
    


(All Year/Month)

https://nunofcguerreiro.com/api-euromillions?result=2018-03

https://nunofcguerreiro.com/api-euromillions-xml?result=2018-03

https://nunofcguerreiro.com/api-euromillions-json?result=2018-03

