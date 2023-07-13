import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { PrivateRoute } from './PrivateRoute';
import Login from '../Login/Login';
import { Main } from '../Main/Main';

export const AppRouter = () => {
    return (
        <Router>
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/" element={<PrivateRoute />}>
                    <Route path="/main" element={<Main />} />
                </Route>
            </Routes>
        </Router>
    );
};
