/join     note/Login/join
/login    note/Login/login
/logout   note/Login/logout
/         note/Pages/index
  :id     note/Pages/see

----------------------------------------------------------------------------------------------------

@sns/Base

/login    note/Login?login
/logout   note/Login?logout

@sns/Base @sns/Crud

/         note/Pages?index  @sns/Crud2
  :id     note/Pages?see
