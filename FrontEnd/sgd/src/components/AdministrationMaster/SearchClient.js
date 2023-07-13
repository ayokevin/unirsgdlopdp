import React, { useState, useEffect } from 'react';
import Select from 'react-select';

const SearchClient = ({ clients, onChangeFilter}) => {
  const [selectedClient, setSelectedClient] = useState(null);

  const handleClientChange = (selectedOption) => {
    setSelectedClient(selectedOption);
    onChangeFilter(selectedOption?.value || ''); // Obtener el valor seleccionado o una cadena vacía si no hay selección
  };

  useEffect(() => {
    // console.log('Cliente seleccionado:', selectedClient);
  }, [selectedClient]);

  const options = clients.map((client) => ({
    value: client.client_id,
    label: client.client_name,
  }));

  return (
    <div>
      <div className="form-group col-sm-12">
        <label>Seleccionar cliente:</label>
        <Select
          value={selectedClient}
          onChange={handleClientChange}
          options={options}
          placeholder="Buscar cliente......"
          isClearable
        />
      </div>
    </div>
  );
};

export default SearchClient;
