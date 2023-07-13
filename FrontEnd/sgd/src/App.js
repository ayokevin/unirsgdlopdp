import React from 'react';
import { AppRouter } from './components/Router/AppRouter';
import { AuthProvider } from './components/Context/AuthContext';

export default function App() {
  return (
    <div>
      <AuthProvider>
        <AppRouter></AppRouter>
      </AuthProvider>
    </div>
  )
}
