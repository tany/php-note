.sys/          sys/Main/index

  db/          sys/db/Database/index
    :id        sys/db/Database/see
    :db/       sys/db/Collection/index
      :id      sys/db/Collection/see
      :coll/   sys/db/Document/index
        :id    sys/db/Document/see

  user/        sys/User/index
    :id        sys/User/see

# --------
# [method]
# items/ sns/user/index RESOURCES
#   :id/ sns/user/see POST

# [chain]
# :id/ sns/user/see
#   -> sns/mobile/convert
