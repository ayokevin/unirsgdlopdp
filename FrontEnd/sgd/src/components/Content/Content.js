import React, { useContext } from 'react';
import { AuthContext } from '../Context/AuthContext';
import { Client } from '../AdministrationMaster/Client';
import { Contact } from '../Contact/Contact';
import { DepartmentMaster } from '../DepartmentMaster/Department';
import { UserSec } from '../UserSecMaster/UserSec';
import { Employees } from '../Employees/Employees';
import { FollowUp } from '../FollowUp/FollowUp';
import { Processes } from '../Processes/Processes';
import { DepartmentUser } from '../Department/Department';
import { Actions } from '../Actions/Actions';

export default function Content() {
  const { selectedOption } = useContext(AuthContext);

  const renderContent = () => {
    switch (selectedOption) {
      case 'clientMaster':
        return <Client />;
      case 'departmentMaster':
        return <DepartmentMaster />;
      case 'userMaster':
        return <UserSec />;
      case 'contact':
        return <Contact />;
      case 'employees':
        return <Employees />;
      case 'followUp':
        return <FollowUp />;
      case 'processes':
        return <Processes />;
      case 'actions':
        return <Actions />;
      case 'department':
        return <DepartmentUser />;
      default:
        return null;
    }
  };

  return (
    <div className="content-wrapper">
      {renderContent()}
    </div>

  );
}
