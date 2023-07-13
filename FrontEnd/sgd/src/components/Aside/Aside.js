import React, { useState, useContext, useEffect } from 'react';
import { fetchJson } from '../../hooks/useFetchJson';
import { AuthContext } from '../Context/AuthContext';

export default function Aside() {
  const [menuData, setMenuData] = useState(null);
  const { setSelectedOption } = useContext(AuthContext);
  const { loginData} = useContext(AuthContext);
  const [openMenus, setOpenMenus] = useState([]);

  const handleMenuClick = (index) => {
    if (openMenus.includes(index)) {
      setOpenMenus(openMenus.filter((item) => item !== index));
    } else {
      setOpenMenus([...openMenus, index]);
    }
  };

  const handleMenuOptionClick = (menuIndex, optionIndex) => {
    const selectedItem = menuData[menuIndex];
    const selectedOption = selectedItem.option[optionIndex];
    setSelectedOption(selectedOption.dirContent);
  };

  useEffect(() => {
    const fetchData = async () => {
      try {
        const requestData = {
          userId: loginData.userSecId,
          fatherId: "0"
        };
  
        const responseData = await fetchJson('http://localhost:3000/app/apiOption/apiOption.php/getOptionList', requestData);
        setMenuData(responseData.data);
        
      } catch (error) {
        console.error(error);
      }
    };
    fetchData();
  }, [loginData.userSecId]);

  return (
    <aside className="main-sidebar sidebar-dark-primary elevation-4" style={{ height: '100vh' }}>
      <span to="index3.html" className="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" className="brand-image img-circle elevation-3" style={{ opacity: '.8' }} />
        <span className="brand-text font-weight-light">SGD Universal</span>
      </span>
      <div className="sidebar">
        <div className="user-panel mt-3 pb-3 mb-3 d-flex">
          <div className="image">
            <img src='dist\img\SGD\U.png' alt="AdminLTE Logo" className="img-circle elevation-2" />
          </div>
          <div className="info">
            <span to="#" className="d-block" style={{  cursor: 'pointer', color: 'white' }}>{loginData.userFirstName} {loginData.userLastName}</span>
          </div>
        </div>
        {menuData && (
          <nav className="mt-2">
            <ul className="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              {menuData.map((item, index) => (
                <li className={`nav-item ${openMenus.includes(index) ? 'menu-is-opening menu-open' : ''}`} key={index}>
                  <span to="#" className="nav-link active" onClick={() => handleMenuClick(index)}>
                    <i className={item.icon} />
                    <p>
                      {item.name}
                      <i className="right fas fa-angle-left" />
                    </p>
                  </span>
                  {item.option && (
                    <ul className="nav nav-treeview" style={{ display: openMenus.includes(index) ? 'block' : 'none' }}>
                      {item.option.map((subItem, subIndex) => (
                        <li className="nav-item" key={subIndex}>
                           <span href={subItem.url} className="nav-link" onClick={() => handleMenuOptionClick(index, subIndex)}>
                            <i className={subItem.icon} />
                            <p>{subItem.name}</p>
                          </span>
                        </li>
                      ))}
                    </ul>
                  )}
                </li>
              ))}
            </ul>
          </nav>
        )}
      </div>
    </aside>
  )
}