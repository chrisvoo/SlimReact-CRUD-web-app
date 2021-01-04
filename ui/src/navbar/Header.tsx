import React from 'react';
import { NavDropdown, Navbar, Nav } from 'react-bootstrap';
import { Link, NavLink } from 'react-router-dom';

export function Header() {
  return (
    <header>
      <Navbar collapseOnSelect bg="dark" expand="lg" fixed="top" className="navbar navbar-expand-md navbar-dark">
        <Navbar.Brand as={Link} to="/">HR App</Navbar.Brand>
        <Navbar.Toggle aria-controls="responsive-navbar-nav" />
        <Navbar.Collapse id="responsive-navbar-nav">
          <Nav className="mr-auto">
            <NavDropdown title="Organization" id="dropDepartments">
              <NavDropdown.Item as={NavLink} to="/departments">Departments</NavDropdown.Item>
              <NavDropdown.Item as={NavLink} to="/employees">Employees</NavDropdown.Item>
            </NavDropdown>
            <NavDropdown title="Reports" id="dropReports">
              <NavDropdown.Item as={NavLink} to="/report/highest_salaries">Highest salaries</NavDropdown.Item>
              <NavDropdown.Item as={NavLink} to="/report/expensive_departments">Expensive departments</NavDropdown.Item>
            </NavDropdown>
          </Nav>
        </Navbar.Collapse>
      </Navbar>
    </header>
  );
}
