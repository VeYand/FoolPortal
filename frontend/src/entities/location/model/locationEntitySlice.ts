import {createSlice, PayloadAction} from '@reduxjs/toolkit'
import {LocationData} from 'shared/types'

type LocationEntitySlice = {
	locations: LocationData[],
	initialized: boolean,
	loading: boolean,
}

const emptyUser: LocationEntitySlice = {
	locations: [],
	initialized: false,
	loading: true,
}

const initialState: LocationEntitySlice = emptyUser

const locationEntitySlice = createSlice({
	name: 'locationEntity',
	initialState,
	reducers: {
		setLocations: (state: LocationEntitySlice, action: PayloadAction<LocationData[]>) => {
			state.locations = action.payload
			state.initialized = true
			state.loading = false
		},
		setLoading: (state, action: PayloadAction<boolean>) => {
			state.loading = action.payload
		},
		setInitialized: (state, action: PayloadAction<boolean>) => {
			state.initialized = action.payload
		},
	},
})

export {
	locationEntitySlice,
}