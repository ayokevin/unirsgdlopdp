import { React, useEffect } from 'react';
import { toast, ToastContainer } from 'react-toastify';
import "react-toastify/dist/ReactToastify.css";

export const Notify = ({ message, type }) => {
  useEffect(() => {
    const showNotification = () => {
      if (type === 'error') {
        toast.error(`${message}`, {
          position: 'top-right',
          autoClose: 5000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          progress: undefined,
          theme: 'light',
        });
      } else if (type === 'success') {
        toast.success(`${message}`, {
          position: "top-right",
          autoClose: 5000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          progress: undefined,
          theme: "colored",
        });
      }
    };

    showNotification();

    return () => {
      toast.dismiss();
    };
  }, [message, type]);

  return <ToastContainer />;
};