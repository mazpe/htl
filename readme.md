# Business Requirements

The problem to solve is the management of orders into our system. As a HTL admin, I want to be able to:
- Create, update and delete a key item
- Create, update and delete a vehicle
- Create, update and delete a technician
- Create, update and delete an order
- List orders by keys, vehicles, and technician

Keep in mind there is a relationship between keys and vehicles.
Technical Requirements

### Backend
- CRUD for keys, vehicles, techs, and orders using REST API.
- Validate request parameters
- Use MySQL database to store data

### Frontend
- Allow to select key in order creation from an existent list related to vehicle information.
- Allow to select technician in order creation from an existent list.
- The technician name should be showed as Last Name, First Name.
- Use HTTP request to access the REST API.

### Data
The following are minimum required fields for the data, feel free to add as many as needed to meet goals and fit standards.
- Keys -> Item Name, Description, Price
- Vehicles -> Year, Make, Model, VIN
- Technician -> First Name, Last Name, Truck Number

### Others
- All source code (including database scripts) must be stored in git repository (you can send it as github or bitbucket link).
- Candidates are free to use any libraries, however you must incorporate Laravel's framework as part of your solution
- Implement unit tests where necessary
- Follow language specific style guidelines and standards (psr, phplint, jslint, etc.)

### What we expect from you?
Production ready solution that you are proud of

