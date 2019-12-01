<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="/">
    <html>
      <head>
        <title>My Cookbook</title>
        <link href="../../css/index.css" rel="stylesheet" type="text/css"/>
        <style type="text/css">
          h1 { font-style: italic ; color: green }
          table { width: 45%; margin: 10px; display: inline-block; vertical-align: top }
          .name { background-color: lightgreen }
          .description { color: green }
          .from { font-style: italic; text-align: center }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h1>My Recipe Collection</h1>
          </div>
          <xsl:for-each select="cookbook/recipe">
            <table border="1">
              <tr class="name">
                <th colspan="3"><xsl:value-of select="name"/></th>
              </tr>
              <tr>
                <td><xsl:value-of select="skill-level"/></td><td><xsl:value-of select="cooking-time"/></td><td><xsl:value-of select="note"/></td>
              </tr>
              <tr class="description">
                <td colspan="3"><xsl:value-of select="description"/></td>
              </tr>
              <xsl:for-each select="ingredient">
                <tr>
                  <td colspan="2"><xsl:value-of select="name"/></td>
                  <td><xsl:value-of select="amount"/><xsl:value-of select="unit"/></td>
                </tr>
              </xsl:for-each>
              <tr>
                <td colspan="3">
                <ul class="indent">
                  <xsl:for-each select="preparation">
                    <li><xsl:value-of select="."/></li>
                  </xsl:for-each>
                  <xsl:for-each select="cooking">
                    <li><xsl:value-of select="."/></li>
                  </xsl:for-each>
                </ul>
                </td>
              </tr>
              <tr class="from">
                <td colspan="3"><xsl:value-of select="from"/></td>
              </tr>
            </table>
          </xsl:for-each>
          <hr/>
          <span class="fl">
            [<a href="index.html">Return</a>]
          </span>
          <span class="fr">
            by Sun Zhicheng
          </span>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>