FROM ubuntu:18.04 
MAINTAINER ELIZAVELSMITH ELIZAVELSMITH@GMAIL.COM
 
RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get install -y build-essential
 
