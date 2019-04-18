library(naivebayes)
#install.packages("gmodels")
library(ggplot2)
library(gmodels)
library(caret)
library(dplyr)
library(psych)
library(tidyverse)
library(tm)
data <- read.csv("F:/study/masters/1sem/ait-580/assignment-7/spam.csv")
head(data)
# remove columns 3 to 5 since no data
colnames(data) <- c("label","text")
data <- data[,1:2]
str(data)
prop.table(table(data$label))
data$length <- str_length(data$text)
#head(data)

# tokenization
token <- VCorpus(VectorSource(data$text))
inspect(token[1:3])

#Document Preprocessing

Sys.setlocale("LC_ALL", "C")
clean_token<-tm_map(token,removeWords,stopwords(kind="english"))
clean_token<-tm_map(token,stripWhitespace)
clean_token<-tm_map(token,content_transformer(tolower))
clean_token<-tm_map(token,removePunctuation)
clean_token<-tm_map(token,removeNumbers)

#converting to document matrix
docmatrix <- DocumentTermMatrix(clean_token)
docmatrix

#Find the frequently occured words

freq <- findFreqTerms(docmatrix,10)
length(freq)
freq[1:50]

#Data partitioning into training 80% and testing data 20%

#Splitting doc term matrix
docmatrix_train <- docmatrix[1:4457,]
docmatrix_test <- docmatrix[4458:5572,]

#splitting tokens
token_train <- clean_token[1:4457]
token_test <- clean_token[4458:5572]

#splitting raw data
data_train <- data[1:4457,]$label
data_test <- data[4458:5572,]$label
prop.table(table(data_train))
prop.table(table(data_test))

#creating new doc term matrix for training and testing
newdtm_train <- docmatrix_train[,freq]
newdtm_test <- docmatrix_test[,freq]

#newdtm_test$dimnames$Terms

#function for counting the frequency of each word
count_words <- function(x){
  x <- ifelse(x>0,"Yes","No") 
}

#generate training and test datasets

sms_train <- apply(newdtm_train, 2,count_words)
sms_test <- apply(newdtm_test, 2,count_words)
#head(sms_train,1)

#Running model by applying Naive bayes classification
sms_model <- naive_bayes(sms_train,data_train)
#sms_model
#Running model with test data
pred <- predict(sms_model,sms_test)
#table(pred,data_test)
#Generate confusion matrix
confusionmat <- as.data.frame(table(pred,data_test))
#confusionmat$Freq[1]
attach(confusionmat)
Accuracy <- ((Freq[1]+Freq[4])/(sum(Freq)))*100
Accuracy
Precision <- ((Freq[4])/(Freq[4]+Freq[2]))
Precision
Recall <- ((Freq[4]/(Freq[4]+Freq[3])))
Recall
F1 <- 2*((Precision*Recall)/(Precision+Recall))*100
F1

