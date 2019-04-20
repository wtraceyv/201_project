﻿USE [master]
GO

DROP DATABASE [appReviewDB]
GO

CREATE DATABASE [appReviewDB]
GO

USE [appReviewDB]
GO

CREATE TABLE users (
	userId			INT					PRIMARY KEY		IDENTITY,
	userName		VARCHAR(30)			NOT NULL,
	pw				VARCHAR(30)			NOT NULL,
	rank			INT					NOT NULL
)
GO

CREATE TABLE apps (
	appId			INT					PRIMARY KEY		IDENTITY,
	appName			VARCHAR(50)			NOT NULL,
	category		VARCHAR(25)			NOT NULL, 
	appDescription          VARCHAR(300)                            NOT NULL,
	price                   DOUBLE                          NOT NULL
)
GO

CREATE TABLE ratings (
	ratingId		INT					PRIMARY KEY		IDENTITY,
	rating			INT					CHECK (rating BETWEEN 1 AND 5),
	ratingComment	VARCHAR(300),
	appId			INT					FOREIGN KEY REFERENCES apps(appId),
	userId			INT					FOREIGN KEY REFERENCES users(userId)
)
GO
