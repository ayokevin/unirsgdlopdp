import React, { useContext } from 'react';
import { Route, Navigate } from 'react-router-dom';
import { AuthContext } from '../Context/AuthContext';

export const PublicRoute = ({ element: Element, restricted, ...rest }) => {
    const { isLoggedIn } = useContext(AuthContext);

    return (
        <Route
            {...rest}
            element={
                isLoggedIn && restricted ? (
                    <Navigate to="/dashboard" replace />
                ) : (
                    <Element />
                )
            }
        />
    );
};
