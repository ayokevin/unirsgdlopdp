import React, { useState, useEffect } from 'react';
import Select from 'react-select';
import { fetchJson } from '../../hooks/useFetchJson';

const SearchDepartment = ({ onChangeFilter}) => {
  const [selectedData, setSelectedData] = useState(null);
  const [clients, setClients] = useState([]);

  const handleDataChange = (selectedOption) => {
    setSelectedData(selectedOption);
    onChangeFilter(selectedOption?.value || ''); // Obtener el valor seleccionado o una cadena vacía si no hay selección
  };

  const fetchDataClient = async () => {
    try {
        const requestData = {};

        const responseData = await fetchJson(
            'http://localhost:3000/app/apiClient/apiClient.php/listClient',
            requestData
        );

        setClients(responseData.data);
    } catch (error) {
        console.error(error);
        // Maneja el error de la manera que prefieras
    }
};

  useEffect(() => {
    fetchDataClient();
  }, [selectedData]);

  const options = clients.map((data) => ({
    value: data.client_id,
    label: data.client_name,
  }));

  return (
    <div>
      <div className="form-group col-sm-12">
        <label>Seleccionar cliente:</label>
        <Select
          value={selectedData}
          onChange={handleDataChange}
          options={options}
          placeholder="Buscar cliente......"
          isClearable
        />
      </div>
    </div>
  );
};

export default SearchDepartment;
