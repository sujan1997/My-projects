library(tidyverse)
library(ggplot2)
library(plyr)
library(corrplot)
data <- read.csv('F:/study/masters/1sem/ait-580/final project/Traffic_Violations.csv')

#remove empty rows and rows with NA's
data <- na.omit(data)

#select required columns
data <- subset(data,select=c(7:19,21,28:30,32))
data <- data.frame(data)

#Handling categorical variables i.e converting Yes ->1, No->0
data$Accident <- ifelse(data$Accident == "Yes",1,0)
data$Belts <- ifelse(data$Belts == "Yes",1,0)
data$Personal.Injury <- ifelse(data$Personal.Injury == "Yes",1,0)
data$Property.Damage <- ifelse(data$Property.Damage == "Yes",1,0)
data$Fatal <- ifelse(data$Fatal == "Yes",1,0)
data$Commercial.License <- ifelse(data$Commercial.License == "Yes",1,0)
data$HAZMAT <- ifelse(data$HAZMAT == "Yes",1,0)
data$Commercial.Vehicle <- ifelse(data$Commercial.Vehicle == "Yes",1,0)
data$Alcohol <- ifelse(data$Alcohol == "Yes",1,0)
data$Work.Zone <- ifelse(data$Work.Zone == "Yes",1,0)
data$Contributed.To.Accident <- ifelse(data$Contributed.To.Accident == "Yes",1,0)
attach(data)
summary(Accident)
summary(Belts)
summary(Personal.Injury)
summary(Property.Damage)
summary(Fatal)
summary(Commercial.License)
summary(HAZMAT)
summary(Commercial.Vehicle)
summary(Alcohol)
summary(Work.Zone)
summary(Contributed.To.Accident)

#count number of male,female violate dthe traffic rules
gen <- count(Gender)
#Generating visualizations
#ggplot(gen,aes(x=x,y=freq))+geom_point()
plot(Gender,col="Blue")+title(xlab='Gender',ylab = 'Count')

#How many traffic violations are recorded in respective years
yrs <- subset(data,Year>1990 & Year<2018)
filter_yrs <- yrs$Year
plt <- as.data.frame(table(filter_yrs))
ggplot(plt,aes(x=filter_yrs,y=Freq))+geom_point(col="blue")+labs(title ="Violations recorded in respective years",x="Year",y="Number of violations")

#Box plot representing count of violations in all states compared to drivers violated in their own state
st <- as.data.frame(table(State))
drst <- as.data.frame(table(Driver.State))
st <- st[sample(1:nrow(st),69,replace= FALSE),]
names(drst)[1] <- paste("State") 
df <- merge(st,drst,by="State")
df <- na.omit(df)
df <- df[,2:3]
names(df)[1] <- paste("Violations_allstates")
names(df)[2] <- paste("Violations_ownstate")
boxplot(df,ylim=c(0,3000),col=c("blue","brown"))

#correlation test & hypothesis test
cor.test(df$`Violations_allstates`,df$`Violations_ownstate`)
library(ggpubr)
ggscatter(df,x='Violations_allstates',y='Violations_ownstate',add = "reg.line",conf.int = TRUE,cor.coef = TRUE,cor.method = "pearson",color = 'blue')

co_matrix <- cor(df)
corrplot(co_matrix)

#Generating logistic reression model & hypothesis test
dat <- data[,c(3,11)]
dat

summary(glm(Accident~Alcohol,family = binomial(link=logit)))




