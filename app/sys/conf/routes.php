.sys/          sys/Main/index

  db/          sys/db/Databases/index
    :id        sys/db/Databases/see
    :db/       sys/db/Collections/index
      :id      sys/db/Collections/see
      :coll/   sys/db/Documents/index
        :id    sys/db/Documents/see

  user/        sys/Users/index
    :id        sys/Users/see

# --------
# [method]
# items/ sns/user/index RESOURCES
#   :id/ sns/user/see POST

# [chain]
# :id/ sns/user/see
#   -> sns/mobile/convert
