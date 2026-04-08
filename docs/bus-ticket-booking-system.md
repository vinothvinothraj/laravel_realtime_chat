# Bus Ticket Booking System

## Purpose

This document defines the complete flow and feature set for a modern bus ticket booking system to be built in this project. It is written as a product and system blueprint only. No code is included.

The goal is to describe what a complete, real-world bus booking platform should support end to end:

- search and discovery
- seat selection
- fare calculation
- reservation hold
- payment
- ticket issuance
- boarding
- cancellation and refund handling
- operator and admin management
- reporting and auditability

## High-Level System Vision

The system should work like a modern travel marketplace and an operator console at the same time.

Passengers should be able to:

- find routes quickly
- compare trips
- see live seat availability
- select exact seats
- pay securely
- receive digital tickets
- manage or cancel bookings

Operators and admins should be able to:

- create routes and schedules
- assign buses and drivers
- control seat maps and trip capacity
- monitor bookings
- manage cancellations and refunds
- run promotions and pricing rules
- view sales and occupancy reports

## Core Roles

### Passenger

The passenger is the end customer who searches, books, pays, and travels.

### Operator

The bus company or transport operator manages vehicles, schedules, fares, and trip execution.

### Admin

The platform administrator manages the whole system, operators, users, permissions, and policies.

### Driver

The driver sees trip manifests, boarding information, and passenger lists where needed.

### Support Staff

Customer support handles booking issues, refunds, schedule changes, and passenger help.

## Complete Passenger Flow

### 1. Search Trip

The passenger starts by entering:

- origin
- destination
- travel date
- passenger count
- optional filters such as sleeper, AC, non-AC, express, luxury, or lowest price

The search results should show:

- departure time
- arrival time
- trip duration
- route name
- operator name
- bus type
- available seats
- price
- discounts or offers
- rating or reliability indicators if available

### 2. Select Trip

The passenger selects a trip based on:

- time
- price
- operator
- seating type
- seat availability

### 3. View Seat Map

The seat map should show:

- available seats
- booked seats
- blocked seats
- female-only or special seats if supported
- front row or preferred seats if allowed
- aisle/window preference if the layout supports it

### 4. Add Passenger Details

The passenger enters:

- full name
- phone number
- email address
- identity document if required
- boarding preferences or notes if needed

For multi-passenger bookings:

- each seat can have a passenger profile
- one main contact should exist for the booking

### 5. Review Fare

Before payment, the system should calculate:

- base fare
- seat-based fare differences
- service fee
- tax
- discounts
- promo codes
- cancellation policy
- total payable amount

### 6. Reserve Seat Temporarily

When a seat is selected, it should be held for a short time.

The system should:

- lock the selected seat
- start a reservation timer
- release the seat automatically if payment is not completed in time

This prevents double booking.

### 7. Payment

The passenger pays using supported methods such as:

- card
- mobile wallet
- bank transfer
- local payment gateway
- cash only if the operator allows it for offline bookings

The payment process should support:

- success
- failure
- pending
- retry
- partial payment if your business model requires it

### 8. Booking Confirmation

After successful payment:

- booking is confirmed
- seat status becomes booked
- ticket reference is generated
- digital ticket is issued
- notification is sent by email and SMS

### 9. Ticket Usage

The passenger can use:

- QR code ticket
- booking reference number
- passenger name and phone number

At boarding, staff can verify:

- ticket validity
- route and trip
- passenger identity
- seat assignment

### 10. Cancellation or Reschedule

Depending on policy, the passenger may:

- cancel a booking
- request a refund
- reschedule to another trip
- partially cancel if multiple seats exist

The system should calculate:

- refund amount
- penalty
- no-refund window
- reschedule fee if applicable

## Booking Lifecycle States

The booking should move through clear states.

Suggested states:

- draft
- seat_held
- payment_pending
- confirmed
- checked_in
- boarded
- completed
- cancelled
- expired
- refunded
- partially_refunded

Suggested payment states:

- unpaid
- pending
- paid
- failed
- reversed
- refunded

Suggested seat states:

- available
- held
- booked
- blocked
- released

## Complete Operator Flow

### 1. Route Setup

The operator defines:

- source city
- destination city
- intermediate stops
- trip duration estimate
- route code
- route frequency

### 2. Schedule Setup

The operator creates trip schedules:

- departure date and time
- arrival estimate
- assigned bus
- assigned driver
- assigned conductor or support staff if needed

### 3. Bus Setup

Each bus should have:

- registration number
- seat capacity
- seat layout
- bus type
- amenities
- active/inactive status

### 4. Pricing Setup

Operators should manage:

- fixed pricing per route
- seasonal pricing
- peak hour pricing
- seat-class pricing
- discounts

### 5. Booking Monitoring

Operators should see:

- trips with live bookings
- seat occupancy
- remaining capacity
- revenue summary
- last-minute cancellations

### 6. Trip Execution

Before departure, staff can:

- view passenger manifest
- check boarded passengers
- mark no-shows
- print manifests if required

### 7. Trip Completion

After the trip:

- mark trip completed
- close booking window
- collect trip analytics
- store trip performance metrics

## Admin Flow

The admin should manage the whole platform:

- users and roles
- operator onboarding
- route governance
- commission rules
- promotions
- payment gateways
- support cases
- audit logs
- reports and dashboards

All admin and operator CRUD screens should be built inside the authenticated Laravel application layout so they share the same navigation, header, permissions, and workspace patterns used by the rest of the project.

## Recommended Pages and Screens

### Public or Passenger Screens

- home/search
- search results
- trip details
- seat selection
- passenger details
- payment
- booking success
- booking history
- ticket details
- cancellation/refund page

### Public Mobile Screens

For the payment and checkout experience, create mobile-oriented public screens under the `public/` folder as simple static-first entry screens or prototypes for:

- mobile home/search
- mobile trip list
- mobile seat selection
- mobile passenger details
- mobile payment selection
- mobile payment confirmation
- mobile booking success
- mobile ticket view

These screens should be optimized for small devices first, with large touch targets, short forms, and a minimal step-by-step checkout flow.

### Operator Screens

- operator dashboard
- routes
- schedules
- buses
- seat maps
- bookings
- passenger manifest
- cancellations
- revenue reports

### Operator and Admin CRUD Screens

All CRUD pages for routes, buses, schedules, trips, bookings, operators, refunds, users, promotions, and settings should be available from the Laravel authenticated layout. Each module should support the standard CRUD lifecycle:

- list view
- create view
- edit view
- detail view
- delete or disable action

When possible, keep the layouts consistent with the existing admin workspace in this project so the bus system feels like part of the same platform rather than a separate app.

### Admin Screens

- global dashboard
- operators management
- users management
- trips and routes oversight
- payment logs
- refund approvals
- audit trail
- system settings

## Data Concepts You Will Need

This is a conceptual list only.

### Core Business Objects

- user
- role
- operator
- bus
- seat
- route
- stop
- trip
- trip schedule
- booking
- booking passenger
- ticket
- payment
- refund
- promo code
- notification
- audit log
- boarding record

### Relationships

- one operator owns many buses
- one route has many trips
- one trip uses one bus
- one bus has many seats
- one booking can have one or many passengers
- one booking has one or many tickets
- one booking can have many payment attempts
- one payment can have one refund or none

## Seat Management Rules

The system should clearly manage seat behavior.

Important rules:

- a seat cannot be sold twice
- held seats should expire automatically
- blocked seats should not appear as bookable
- confirmed bookings should lock the seat until cancellation or trip completion
- rescheduling should release one seat and lock another in a single controlled action

## Fare and Pricing Rules

The price engine should be flexible enough for real-world operations.

Possible rules:

- route base fare
- trip time premium
- seat class premium
- early-bird discount
- promo code discount
- operator discount
- festive season pricing
- last-minute booking surcharge

The system should always calculate the final total before payment.

## Notification Flow

Notifications should be sent at key moments:

- trip search result reminder if user abandons booking optionally
- seat reserved
- payment succeeded
- booking confirmed
- ticket issued
- trip schedule changed
- booking cancelled
- refund processed
- boarding reminder

Channels may include:

- email
- SMS
- in-app notification
- WhatsApp or messaging integration if desired

## Cancellation and Refund Flow

Cancellation needs a clear policy.

Recommended flow:

1. passenger requests cancellation
2. system checks whether cancellation is allowed
3. system calculates fee or refund amount
4. payment/refund request is issued
5. booking status changes
6. seat is released if applicable
7. notifications are sent

Possible cancellation outcomes:

- full refund
- partial refund
- no refund
- voucher credit instead of money if business policy allows

## Boarding Flow

At the terminal or boarding point:

1. staff loads trip manifest
2. passenger presents ticket or QR code
3. staff verifies the booking
4. seat and passenger details are checked
5. board status is marked
6. no-show handling is recorded after departure cutoff

## Offline or Counter Booking Flow

Not all users book online.

The system should also support:

- counter bookings
- agent-assisted booking
- phone reservations
- pay-later reservations if enabled

In those cases:

- a staff member creates the booking
- payment may be marked pending or cash collected
- ticket issuance still follows the same lifecycle

## Reporting and Analytics

The system should provide:

- daily bookings
- occupancy by route
- revenue by route
- revenue by operator
- cancellation rate
- no-show rate
- seat utilization
- top-selling routes
- payment success rate
- refund volume

## Security and Control Requirements

The system should include:

- role-based access
- booking ownership checks
- audit logs for critical actions
- payment reconciliation
- anti-double-booking protection
- expiration handling for held seats
- CSRF protection
- strong validation on all booking actions

## Operational Requirements

The system should be built to handle:

- concurrent users booking the same trip
- seat contention at peak time
- gateway latency or payment failures
- schedule changes after some bookings are already confirmed
- trip cancellation by operator

## Real-World Features Worth Including

To match current user expectations, the system should ideally support:

- mobile-first booking UI
- live seat availability
- instant booking confirmation
- digital ticket with QR code
- saved traveler profiles
- booking history
- notifications
- simple refund status tracking
- fast search filters
- multiple passenger booking in one checkout

## Recommended MVP Scope

If you want a safe first release, build these first:

1. route and schedule management
2. trip search
3. seat selection
4. booking hold
5. payment
6. booking confirmation
7. ticket generation
8. booking history
9. cancellation with basic refund rules
10. admin/operator dashboard

## Phase 2 Enhancements

After MVP, add:

- QR boarding verification
- promo engine
- loyalty points
- split payment
- wallet balance
- dynamic pricing
- real-time delay updates
- operator settlement reports
- agent commissions
- support ticketing
- route popularity analytics

## Suggested Build Order

If this is going into the current Laravel project, the safest order is:

1. define the business roles and permissions
2. design the data model conceptually
3. build route and schedule management
4. build search and trip listing
5. build seat locking and booking flow
6. integrate payment
7. issue tickets and notifications
8. add cancellation/refund handling
9. add operator dashboards
10. add admin reporting and audits

## Final Notes

A bus ticket booking system is not just a checkout form. A complete system must manage:

- inventory
- time-based seat locking
- pricing rules
- booking lifecycle
- payment reliability
- cancellation policy
- reporting
- operational workflows

If you build it with those flows in mind from the start, the system will feel much more complete and much safer in the real world.
