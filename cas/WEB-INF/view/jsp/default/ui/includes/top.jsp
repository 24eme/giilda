<%--

    Licensed to Jasig under one or more contributor license
    agreements. See the NOTICE file distributed with this work
    for additional information regarding copyright ownership.
    Jasig licenses this file to you under the Apache License,
    Version 2.0 (the "License"); you may not use this file
    except in compliance with the License.  You may obtain a
    copy of the License at the following location:

      http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing,
    software distributed under the License is distributed on an
    "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
    KIND, either express or implied.  See the License for the
    specific language governing permissions and limitations
    under the License.

--%>
<!DOCTYPE html>

<%@ page pageEncoding="UTF-8" %>
<%@ page contentType="text/html; charset=UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="spring" uri="http://www.springframework.org/tags" %>
<%@ taglib prefix="form" uri="http://www.springframework.org/tags/form" %>
<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 6 ]><html class="no-js ie6 ielt7 ielt8 ielt9" lang="fr"><![endif]-->
<!--[if IE 7 ]><html class="no-js ie7 ielt8 ielt9" lang="fr"><![endif]-->
<!--[if IE 8 ]><html class="no-js ie8 ielt9" lang="fr"><![endif]-->
<!--[if IE 9 ]><html class="no-js ie9" lang="fr"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="fr"><!--<![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
	<head>
		<title>Espace des professionnels du Vignoble du Val de Loire : Authentification</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
                <spring:theme code="standard.custom.css.file" var="customCssFile" />
                <link rel="stylesheet" href="<c:url value="${customCssFile}" />" />
                <link rel="icon" href="<c:url value="/favicon.ico" />" type="image/x-icon" />
		<link rel="stylesheet" href="./css/style.css" />
		<script type="text/javascript" src="./js/lib/modernizr-2.js"></script>
	</head>
	<body role="document">
		<div id="page">
			<header id="header" role="banner">
				<div class="gabarit">
					<img class="logo" src="images/logo_new.png" alt="Vins du val de loire" />
					
					<h1>Espace des professionnels du Vignoble du Val de Loire</h1>
				</div>
			</header>
			
			<div id="contenu" class="gabarit cf" role="main">
