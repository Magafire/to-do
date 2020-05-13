# to-do
Run server:
- php bin/console server:run

## Entity
- id (int)
- name (text)
- done (boolean)
- creat_at (date)

## Endpoints

Add new task (POST):
```console
Parmas required: name
http://localhost:8000/new
```

All task (GET):
```console
http://localhost:8000/all
```

Show one task (GET):
```console
http://localhost:8000/show?id=1
```

Edit Task (POST):
```console
Parmas required: id, name
http://localhost:8000/edit
```

 Task done (POST):
```console
Parmas required: id
http://localhost:8000/done
```


 Delete task (DELETE):
```console
Parmas required: id
http://localhost:8000/delete
```
