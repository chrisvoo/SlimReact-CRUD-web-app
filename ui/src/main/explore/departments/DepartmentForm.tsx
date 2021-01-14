import React, { useState, useEffect } from 'react';
import axios from 'axios';
import {
  Container, Row, Col, Card, Button, Form, Alert,
} from 'react-bootstrap';
import { Redirect, useLocation } from 'react-router-dom';
import { useForm /* , Controller */ } from 'react-hook-form';
import ApiCalls from '../../shared/ApiCalls';

export function DepartmentForm() {
  const [isLoading, setLoading] = useState(false);
  const [successSubmit, setSuccessSubmit] = useState(false);
  const [errorResponse, setErrorResponse] = useState({ error: false, description: '' });
  const { pathname } = useLocation();
  const {
    handleSubmit, register, errors, setValue,
  } = useForm();

  const lastRouteParam = pathname.substring(pathname.lastIndexOf('/') + 1);

  useEffect(() => {
    if (lastRouteParam !== 'create') {
      ApiCalls.getDepartment(lastRouteParam,
        (response: any) => {
          setValue('name', response.data.name);
          setValue('id', lastRouteParam);
        },
        (error: Error) => {
          setErrorResponse({
            error: true,
            description: error.message,
          });
        });
    }
  }, []);

  // eslint-disable-next-line no-console
  const onSubmit = (d: any) => {
    setLoading(true);

    // we manage both creation and modification of the department
    axios({
      method: lastRouteParam === 'create' ? 'post' : 'put',
      url: `/api/department${lastRouteParam === 'create' ? '' : `/${lastRouteParam}`}`,
      data: {
        name: d.name,
      },
    }).then(() => {
      setSuccessSubmit(true);
    })
      .catch((error) => {
        setErrorResponse({
          error: true,
          description: error.message,
        });
      })
      .finally(() => {
        setLoading(false);
      });
  };

  if (successSubmit) {
    return <Redirect to="/departments" />;
  }

  return (
    <Card>
      <Card.Header>Departments management</Card.Header>
      <Card.Body>
        <Card.Text>
          Here you can manage the departments.
        </Card.Text>
        <Container>
          <Form noValidate onSubmit={handleSubmit(onSubmit)}>
            <Row>
              <Col>
                <Form.Row>
                  <Form.Group as={Col} controlId="formGridName">
                    <Form.Label>Department&apos;s name</Form.Label>
                    <Form.Control
                      name="name"
                      ref={register({ required: true })}
                      type="text"
                      placeholder="Enter name"
                      isInvalid={errors.name}
                    />
                    <Form.Control.Feedback
                      type="invalid"
                    >
                      The name is required
                    </Form.Control.Feedback>
                  </Form.Group>
                </Form.Row>
              </Col>
            </Row>
            <Row>
              <Col>
                <Button
                  variant="primary"
                  style={{ float: 'right' }}
                  type="submit"
                  disabled={isLoading}
                >
                  {isLoading ? 'Loading…' : 'Save'}
                </Button>
              </Col>
            </Row>
          </Form>
        </Container>
      </Card.Body>
      <Card.Footer>
        { errorResponse.error && (
          <Alert variant="danger">
            {errorResponse.description}
          </Alert>
        )}
      </Card.Footer>
    </Card>
  );
}
