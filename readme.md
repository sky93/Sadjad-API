[![Sadjad University API](http://3.1m.yt/_jkjLdr.png)](https://api.sadjad.ac.ir/)

# Sadjad University API
A simple, lightweight and fast API for Sadjad University of Technology

## Current End Points
### `/v1/student_schedule` or `/v2/stu/schedule`
Sample: `https://api.sadjad.ac.ir/v1/student_schedule?username=92412147&password=XXXXXXXX`

Or: `https://api.sadjad.ac.ir/v2/stu/schedule?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

---
### `/v1/internet_credit`
Sample: `https://api.sadjad.ac.ir/v1/internet_credit?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

---
### `/v1/self_service_credits`
Sample: `https://api.sadjad.ac.ir/v1/self_service_credits?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

---
### `/v1/self_service_menu`
Sample: `https://api.sadjad.ac.ir/v1/self_service_menu?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

---
### `/v1/exams`
Sample: `https://api.sadjad.ac.ir/v1/exams?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

Returns all exams with their dates of the current semester.

---
### `/v1/library`
Sample: `https://api.sadjad.ac.ir/v1/library?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

Returns useful information about library account.

---
### `/v2/stu/profile`
Sample: `https://api.sadjad.ac.ir/v2/stu/profile?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

Returns useful information about stu (student) account.

---
### `/v2/stu/exam_card`
Sample: `https://api.sadjad.ac.ir/v2/stu/exam_card?username=92412147&password=XXXXXXXX`

Supported methods are `post` and `get`

Returns exams card in PDF format.

_Note: This endpoint works only in exams duration._

## Important Privacy Note
As you see in the code no information are stored. We don't even use databases or log files. The username or password you provide is only used for loging in to the student panel.


## License
This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

