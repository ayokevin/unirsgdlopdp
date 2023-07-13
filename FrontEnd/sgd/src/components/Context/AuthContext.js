import React, { createContext, useState } from 'react';

export const AuthContext = createContext();


export const AuthProvider = ({ children }) => {
  
  const [selectedOption, setSelectedOption] = useState(null); // Agregar el estado para la opción seleccionada
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [loginData, setLoginData] = useState({
    userSecId: '',
    userFirstName: '',
    userLastName: '',
    userApplication: '',
    statusName: '',
    clientId: ''
  });

  const updateLoginData = (newData) => {
    setLoginData((prevData) => ({
      ...prevData,
      ...newData
    }));
  };

  return (
    <AuthContext.Provider value={{selectedOption,setSelectedOption, isLoggedIn, setIsLoggedIn, loginData, updateLoginData }}>
      {children}
    </AuthContext.Provider>
  );
};
