#!/usr/bin/python

import cgi, cgitb

form = cgi.FieldStorage()

searchQuery = form.getvalue('searchQuery')

print "Content-type:text/html\r\n\r\n"
print '<html>'
print '<head>'
print '<title>Hello Word - First CGI Program</title>'
print '</head>'
print '<body>'
print '<h2>So here\'s what I\'ve got: </h2>'
print '<p>You searched: %s</p>' % (searchQuery)
print '</body>'
print '</html>'
