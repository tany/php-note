/join     note/Login?join
/login    note/Login?login
/logout   note/Login?logout

@sns/Base

/         note/Pages?index
  :id     note/Pages?see

.sys/          sys/Main?index
  db/          sys/db/Databases?index
    :id        sys/db/Databases?see
    :db/       sys/db/Collections?index
      :id      sys/db/Collections?see
      :coll/   sys/db/Documents?index
        :id    sys/db/Documents?see
  user/        sys/Users?index
    :id        sys/Users?see

.sys/                  sys/Main?index
.sys/db/               sys/db/Databases?index
.sys/db/:id            sys/db/Databases?see
.sys/db/:db/           sys/db/Collections?index
.sys/db/:db/:id        sys/db/Collections?see
.sys/db/:db/:coll/     sys/db/Documents?index
.sys/db/:db/:coll/:id  sys/db/Documents?see
