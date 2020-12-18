<?xml version="1.0" encoding="UTF-8"?>
<configuration>
  <system.webServer>
    <rewrite>
        <rules>
        <rule name="webp">
        <match url="(.+)\.(jpe?g|png)$" ignoreCase="false" />
        <conditions logicalGrouping="MatchAll">
        <add input="{HTTP_ACCEPT}" pattern="image/webp" ignoreCase="false" />
        <add input="{DOCUMENT_ROOT}/{R:0}.webp" matchType="IsFile" />
        </conditions>
        <action type="Rewrite" url="{R:0}.webp" logRewrittenUrl="true" />
        <serverVariables>
        <set name="ACCEPTS_WEBP" value="true" />
        </serverVariables>
        </rule>
        </rules>
        <outboundRules rewriteBeforeCache="true">
        <rule name="jpg to webp" preCondition="ResponseIsHtml" enabled="true">
        <match filterByTags="Img" pattern="(.+)\.(jpe?g|png)$" />
        <action type="Rewrite" value="{R:0}.webp" />
        </rule>
        <preConditions>
        <preCondition name="ResponseIsHtml">
        <add input="{RESPONSE_CONTENT_TYPE}" pattern="^text/html" />
        </preCondition>
        </preConditions>
        </outboundRules>
    </rewrite>
    <staticContent>
      <mimeMap fileExtension=".webp" mimeType="image/webp"/>
    </staticContent>
  </system.webServer>
</configuration>
