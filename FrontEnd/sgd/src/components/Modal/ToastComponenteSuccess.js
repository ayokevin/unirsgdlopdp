import React from 'react';

export const ToastComponentSuccess = ({ title, subtitle, body }) => {
  return (
    <div className="toast bg-success fade show" role="alert" aria-live="assertive" aria-atomic="true">
      <div className="toast-header">
        <strong className="mr-auto">{title}</strong>
        <small>{subtitle}</small>
        <button data-dismiss="toast" type="button" className="ml-2 mb-1 close" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div className="toast-body">{body}</div>
    </div>
  );
};

