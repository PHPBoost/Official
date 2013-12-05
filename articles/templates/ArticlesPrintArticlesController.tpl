<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{PAGE_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/print.css" type="text/css" media="screen" />
	</head>
	<body>
	<h1>{TITLE}</h1>
	<div>
	{CONTENTS}
	</div>
	# IF C_SOURCES #
	<hr />
	<div><b> {@articles.sources} : </b># START sources #{sources.COMMA}<a href="{sources.URL}" class="small">{sources.NAME}</a># END sources #</div>
	# ENDIF #
	</body>
</html>