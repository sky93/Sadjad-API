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
### `/v1/self_service_credits` or `/v2/internet/credits`
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

---
### `/v2/stu/grades`
Samples: 

`https://api.sadjad.ac.ir/v2/stu/grades?username=92412147&password=XXXXXXXX`

`https://api.sadjad.ac.ir/v2/stu/grades?username=92412147&password=XXXXXXXX&year=1392&semester=1`

`https://api.sadjad.ac.ir/v2/stu/grades?username=92412147&password=XXXXXXXX&year=1394&semester=3`

Supported methods are `post` and `get`

#### Additional Parameters

| Semester      | Meaning      |
|---------------|--------------|
| year | Desired year |
| semester | Number of semester |

_Note: If `year` and `semster` parameters are not provided, current year and current semester will be assumed._

#####Semester accepted values

| Semester      | Meaning      |
|---------------|--------------|
| 1 | 1st semester |
| 2 | 2nd semester |
| 3 | Summer semester |

---
### `/v2/internet/connection_report`
Samples: 

`http://localhost/sadjad_api/Sadjad-API/public/v2/internet/connection_report?username=92412147&password=XXXXXXXXX&start=XXXXXXXXX&end=XXXXXXXXX&page=1`

Supported methods are `post` and `get`

#### Additional Parameters

| Semester      | Meaning      |
|---------------|--------------|
| start | Start time _(unix format)_ |
| end | End time _(unix format)_ |
| start | Desired page _(default: `1`)_ |



## Important Privacy Note
As you see in the code no information are stored. We don't even use databases or log files. The username or password you provide is only used for loging in to the student panel.


## License
This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

