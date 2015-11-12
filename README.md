## PHP Mysql Connectivity/ Sessions

An simple appliance store has a database to keep track of which customers have ordered which appliances. Resigtered customers can place their orders. Used mysql as backend. 

## Database - Tables:

create table customer ( phone char(10) primary key,
building_num int,
street varchar(20),
apartment varchar(20)
);

create table appliance ( aname varchar(20) primary key,
description varchar (100)
);

create table catalog (aname varchar(20),
config varchar (20),
price decimal(4,2),
status varchar(20),
primary key (aname, config),
foreign key (aname) references appliance(aname)
);

create table orders (phone char(10),
aname varchar(20),
config varchar(20),
o_time datetime,
quantity int,
price decimal(4,2),
status varchar(10),
primary key (phone, aname, config, o_time),
foreign key (phone) references customer(phone),
foreign key (aname, configuration) references catalog(aname, config)
);

## License

Copyright (c) 2011 until today, Manuel Reinhard

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
