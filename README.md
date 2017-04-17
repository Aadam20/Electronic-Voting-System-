# Electronic Voting System
Facial Recognition - Complete<br>
Blockchain Implementation - Complete<br><br>

# Requirements to Run on Local Machine
Apache and MySQL installed on local machine.<br><br>

# Instructions to Run on Local Machine
1. Folder must be copied <b>as is</b> onto local machine where it can be referenced by http://localhost.
2. If MySql Database on local machine requires a password:
   2i)Navigate to and open the following files : ElectronicVotingSystem\communication\lib.php, line 5<br>
                                                 ElectronicVotingSystem\data.php, line 14<br>
                                                 ElectronicVotingSystem\admin\login.php, line 5<br>
                                                 ElectronicVotingSystem\admin\lib_admin.php, line 17<br>
                                                 ElectronicVotingSystem\admin\index.php, line 52<br>
     Enter the MySql Database password inside the quotation marks after "root"

3. Access website on browers with URL: http://localhost/ElectronicVotingSystem/

# Comments on System
1. "BlockChain Server Files" and blockchain.js are there to demonstrate the implementation of a blockchain network working concurrently with the current system if the files were located on a Node.js server with an IP Address of 45.55.80.29.

2. ElectronicVotingSystem\js\kairos.js was originally created by Cole Calistra and uploaded to GitHub to make the task of implementing KAIROS into a project easier. https://github.com/kairosinc/Kairos-SDK-Javascript 

3. The Graph on the Client's page can be displayed by logging into the admin page with the URL: http://localhost/ElectronicVotingSystem/admin , using the credentials ID: "1234" and Password "1234" then scrolling to the bottom of the page to click the "Release Results" Button. When this is done revisit the Client's page and click the "Content" B


