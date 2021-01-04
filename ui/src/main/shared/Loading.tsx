import React from 'react';
import Spinner from 'react-bootstrap/Spinner';

export function Loading() {
  return (
    <Spinner animation="border" role="status">
      <span className="sr-only">Loading...</span>
    </Spinner>
  );
}
