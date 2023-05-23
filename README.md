# Occupancy rates

The task of the test is to correctly calculate room occupancy rates. Occupancy rates represent the number of occupied versus vacant rooms.

- [x] Room, Booking, Block models.
- [ ] GET /daily-occupancy-rates/{Y-m-d}?product_ids[]=X&room_ids[]=Y...
- [ ] GET /monthly-occupancy-rates/{Y-m}?product_ids[]=X&room_ids[]=Y...
- [ ] POST /booking
- [ ] PUT /booking/{id}

## Local Development

This project uses Docker adapted from an Laravel Initializer template - to build and start the containers use:

```shell
make build
make up
```

Some useful commands can be found inside the Makefile.

### Login:

#### Administrator

- email: user@bookinglayer.com
- password: secret


### Links

- **Your Application** http://localhost
- **API Documentation** http://localhost/api/documentation/
- **Preview Emails via Mailpit** http://localhost:8025
- **Laravel Telescope** http://localhost/telescope
